<!DOCTYPE html>
<html>
<head>
    <title>Zoom Meeting Link for Your Session</title>
</head>
<body>
    <p>Hello {{ $teacherName }},</p>
    <p>Your Zoom meeting for the session has been successfully created. You can join the meeting using the link below:</p>
    <a href="{{ $meetingLink }}" target="_blank">{{ $meetingLink }}</a>
    <p>We wish you a successful meeting!</p>
</body>
</html>
