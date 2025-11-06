<?php
namespace App\Http\Requests\Api\Lessons;

use App\Http\Requests\Base\ApiRequest;
use Illuminate\Support\Facades\Auth;

class AttachmentLessonRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'group_session_id'    => 'required|exists:group_sessions,id',
            'file'         => 'nullable',
            'type'         => 'nullable|in:upload_video,youtube_link,vimeo_link',
            'video_upload' => 'nullable',
            'link'         => 'nullable|active_url',
            'image'        => 'nullable',
        ];
    }
    public function messages()
    {
        return [
            'file.mimes'         => 'The file must be a type of pdf, docx, doc, xls, xlsx, ppt, pptx, zip, or rar.',
            'image.mimes'        => 'The image must be a type of jpg, jpeg, or png.',
            'video_upload.mimes' => 'The video must be a type of mp4, mov, avi, or wmv.',
            'type.in'            => 'The type must be one of the following: upload_video, youtube_link, or vimeo_link.',
            'link.active_url'    => 'The link must be a valid URL.',
            'group_session_id.required' => 'The group session is required.',
        ];
    }
    public function getData()
    {
        $data = $this->validated();
        $data['user_id'] =Auth::user()->id;
        return $data;
    }

}
