<?php

use App\Events\PlanoContratacao\PlanoContratacaoCreated;
use App\Events\PlanoContratacao\PlanoContratacaoDownload;
use App\Events\PlanoContratacao\PlanoContratacaoItemCreated;
use App\Events\PlanoContratacao\PlanoContratacaoItemRemoved;
use App\Events\PlanoContratacao\PlanoContratacaoItemRetificar;
use App\Events\PlanoContratacao\PlanoContratacaoRemoved;
use App\Listeners\PlanoContratacao\GetPlanoContratacaoDownload;
use App\Listeners\PlanoContratacao\SendPlanoContratacaoCreated;
use App\Listeners\PlanoContratacao\SendPlanoContratacaoDeleted;
use App\Listeners\PlanoContratacao\SendPlanoContratacaoItemCreated;
use App\Listeners\PlanoContratacao\SendPlanoContratacaoItemRemoved;
use App\Listeners\PlanoContratacao\SendPlanoContratacaoItemRetificar;
use App\Support\EventManager;

EventManager::listen(PlanoContratacaoCreated::class, SendPlanoContratacaoCreated::class);
EventManager::listen(PlanoContratacaoRemoved::class, SendPlanoContratacaoDeleted::class);
EventManager::listen(PlanoContratacaoItemRemoved::class, SendPlanoContratacaoItemRemoved::class);
EventManager::listen(PlanoContratacaoItemRetificar::class, SendPlanoContratacaoItemRetificar::class);
EventManager::listen(PlanoContratacaoItemCreated::class, SendPlanoContratacaoItemCreated::class);
EventManager::listen(PlanoContratacaoDownload::class, GetPlanoContratacaoDownload::class);
