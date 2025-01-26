<?php

namespace App\Console\Commands;

use App\Jobs\FetchNewsJob;
use App\Models\DataSource;
use Illuminate\Console\Command;
use App\Repositories\DataSource\DataSourceRepositoryInterface;

class FetchNews extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news from all registered data sources';

    protected $dataSourceRepository;

    public function __construct(DataSourceRepositoryInterface $dataSourceRepository)
    {
        parent::__construct();
        $this->dataSourceRepository = $dataSourceRepository;
    }

    public function handle()
    {
        $dataSources = $this->dataSourceRepository->getAll();

        foreach ($dataSources as $dataSource) {
            FetchNewsJob::dispatch($dataSource);
        }

        $this->info('News fetching jobs have been dispatched.');
    }
}
