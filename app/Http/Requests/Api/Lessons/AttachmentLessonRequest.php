<?php
namespace App\Http\Requests\Api\Lessons;

use App\Http\Requests\Base\ApiRequest;

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
            'file'         => 'nullable|mimes:pdf,docx,doc,xls,xlsx,ppt,pptx,zip,rar',
            'type'         => 'nullable|in:upload_video,youtube_link,vimeo_link',
            'video_upload' => 'nullable|mimes:mp4,mov,avi,wmv',
            'link'         => 'nullable|active_url',
            'image'        => 'nullable|mimes:jpg,jpeg,png',
        ];
    }

    public function getData()
    {
        $data = $this->validated();
        return $data;
    }

}
