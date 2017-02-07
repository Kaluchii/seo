<?php

namespace Interpro\Seo;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Interpro\Core\Contracts\Mediator\DestructMediator;
use Interpro\Core\Contracts\Mediator\InitMediator;
use Interpro\Core\Contracts\Mediator\SyncMediator;
use Interpro\Core\Contracts\Mediator\UpdateMediator;
use Interpro\Extractor\Contracts\Creation\CItemBuilder;
use Interpro\Extractor\Contracts\Db\JoinMediator;
use Interpro\Extractor\Contracts\Db\MappersMediator;
use Interpro\Extractor\Contracts\Selection\Tuner;
use Interpro\Seo\Creation\SeoItemFactory;
use Interpro\Seo\Db\SeoCMapper;
use Interpro\Seo\Db\SeoJoiner;
use Interpro\Seo\Executors\Destructor;
use Interpro\Seo\Executors\Initializer;
use Interpro\Seo\Executors\Synchronizer;
use Interpro\Seo\Executors\UpdateExecutor;

class SeoSecondServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(Dispatcher $dispatcher,
                         MappersMediator $mappersMediator,
                         JoinMediator $joinMediator,
                         CItemBuilder $cItemBuilder,
                         InitMediator $initMediator,
                         SyncMediator $syncMediator,
                         UpdateMediator $updateMediator,
                         DestructMediator $destructMediator,
                         Tuner $tuner)
    {
        //Log::info('Загрузка SeoSecondServiceProvider');

        //Фабрике нужен медиатор мапперов и строитель item'ов простых типов, QS мапперу нужна фабрика
        $factory = new SeoItemFactory();
        $mapper = new SeoCMapper($factory, $tuner);
        $cItemBuilder->addFactory($factory);

        $mappersMediator->registerCMapper($mapper);

        //joiner нужен для объединения в запросах,
        //при использовании сортировок и фильтров, здесь через поле скалярного типа
        $joiner = new SeoJoiner();
        $joinMediator->registerJoiner($joiner);

        $initializer = new Initializer();
        $initMediator->registerCInitializer($initializer);

        $synchronizer = new Synchronizer();
        $syncMediator->registerOwnSynchronizer($synchronizer);

        $updateExecutor = new UpdateExecutor();
        $updateMediator->registerCUpdateExecutor($updateExecutor);

        $destructor = new Destructor();
        $destructMediator->registerCDestructor($destructor);
    }

    /**
     * @return void
     */
    public function register()
    {
        //Log::info('Регистрация SeoSecondServiceProvider');

        $config = config('interpro.seo', []);

        $typeRegistrator = App::make('Interpro\Core\Contracts\Taxonomy\TypeRegistrator');

        $configInterpreter = new ConfigInterpreter();

        $manifest = $configInterpreter->interpretConfig($config);

        $typeRegistrator->registerType($manifest);
    }

}
