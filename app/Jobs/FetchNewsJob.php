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

    protected $dataSource;

    public function __construct(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function handle()
    {
        $service = NewsServiceFactory::create($this->dataSource->name);

        if ($service) {
            $service->fetchNews();
        }
    }
}
