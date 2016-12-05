<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Image;

class ResizePostImage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $imagePath;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->imagePath = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         Image::make($this->imagePath)->resize(1080, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('png',80)->save();
    }
}
