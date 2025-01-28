<?php

namespace App\Jobs;

use App\Models\DataSource;
use Illuminate\Bus\Queueable;
use App\Factories\NewsServiceFactory;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data source instance.
     *
     * @var \App\Models\DataSource
     */
    protected $dataSource;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\DataSource $dataSource
     * @return void
     */
    public function __construct(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = NewsServiceFactory::create($this->dataSource->name);

        if ($service) {
            $service->fetchNews();
        }
    }
}
