<?php

namespace App\Console\Commands;

use App\Jobs\FetchNewsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Repositories\DataSource\DataSourceRepositoryInterface;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news from all registered data sources';

    /**
     * The data source repository instance.
     *
     * @var \App\Repositories\DataSource\DataSourceRepositoryInterface
     */
    protected $dataSourceRepository;

    /**
     * Create a new command instance.
     *
     * @param \App\Repositories\DataSource\DataSourceRepositoryInterface $dataSourceRepository
     */
    public function __construct(DataSourceRepositoryInterface $dataSourceRepository)
    {
        parent::__construct();
        $this->dataSourceRepository = $dataSourceRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $dataSources = $this->dataSourceRepository->getAll();

        foreach ($dataSources as $dataSource) {
            FetchNewsJob::dispatch($dataSource);
        }

        Cache::flush();

        $this->info('News fetching jobs have been dispatched.');
    }
}
