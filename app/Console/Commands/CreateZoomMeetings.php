<?php

namespace App\Console\Commands;

use App\Models\GroupSession;
use App\Models\MeetingZoom;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeetingLinkMail;

class CreateZoomMeetings extends Command
{
    protected $signature   = 'zoom:create-meetings';
    protected $description = 'Create Zoom meetings for upcoming sessions';

    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        parent::__construct();
        $this->zoomService = $zoomService;
    }

    public function handle()
    {
        $sessions = GroupSession::with('group')
            ->whereDate('date', Carbon::now()->toDateString()) // Sessions for today
            ->where('is_meeting_created', false)              // Sessions that don't have a meeting link created
            ->get();

        // If there are no sessions to create meetings for
        if ($sessions->isEmpty()) {
            $this->info('No upcoming sessions to create meetings for.');
            return; // Exit if no sessions are found
        }

        foreach ($sessions as $session) {
            $meeting = $this->create_meeting($session); // Call function to create the meeting

            if ($meeting && isset($meeting['join_url'])) {
                // Create new MeetingZoom record
                $meeting_zoom = MeetingZoom::create([
                    'group_session_id'   => $session->id,
                    'teacher_id'         => $session->group->teacher_id,
                    'zoom_id'            => $meeting['id'],
                    'host_id'            => $meeting['host_id'],
                    'host_email'         => $meeting['host_email'],
                    'topic'              => $meeting['topic'],
                    'start_time'         => $meeting['start_time'],
                    'duration'           => $meeting['duration'],
                    'timezone'           => $meeting['timezone'],
                    'start_url'          => $meeting['start_url'],
                    'join_url'           => $meeting['join_url'],
                    'password'           => $meeting['password'],
                    'is_meeting_created' => 1,
                ]);

                // Update session with the meeting link
                $session->update([
                    'is_meeting_created' => 1,
                ]);

                // Send email to teacher with meeting link
                $teacher = $session->group->teacher;
                $teacherName = $teacher->name;  // Assuming teacher's name is stored
                $meetingLink = $meeting['join_url'];

                // Send the email to the teacher
                Mail::to($teacher->email)->send(new MeetingLinkMail($teacherName, $meetingLink));

                $this->info("Created meeting for session on {$session->date}, link: {$meeting['join_url']}");
            } else {
                $this->error("Failed to create meeting for session on {$session->date}");
            }
        }
    }

    // Function to create Zoom meeting
    public function create_meeting($session)
    {
        $start_time = Carbon::parse($session->date . ' ' . $session->start_time)->toIso8601String(); // Start time for the meeting

        // Call ZoomService to create the meeting
        $meeting = $this->zoomService->createMeeting('me', [
            'topic' => "Meeting for session {$session->id}",
            'type'  => 2,                          // Scheduled
            'start_time' => $start_time,           // Set start time
            'duration' => $session->group->duration, // Set duration from session
            'timezone' => 'Asia/Kolkata',          // Timezone
            'settings' => [
                'host_video'                                => true,
                'participant_video'                         => false,
                'mute_upon_entry'                           => true,
                'request_permission_to_unmute_participants' => true,
                'audio'                                     => 'voip',
                'waiting_room'                              => true,
            ],
        ]);

        // If the meeting was created successfully, return the meeting data
        if (isset($meeting['join_url'])) {
            return $meeting;
        }

        return null; // Return null if meeting creation failed
    }
}
