<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use App\Models\ISSQN\RedesimLogJob;
use Throwable;

class ProcessRedesimData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redesim:process {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Redesim Data and log the response';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $debug = $this->option('debug');

        if ($debug) {
            $this->info('Debug mode is ON');
        }

        $url = config('app.url') . "/api/redesim/companies";
        if ($debug) {
            $this->warn('(Debug) $url: ' . $url);
        }
        $token = env('API_REDESIM_TOKEN');
        if ($debug) {
            $this->warn('(Debug) $token: ' . $token);
        }
        try {
            $redesimLogJob = RedesimLogJob::create([
                'q191_started' => now(),
            ]);

            if ($debug) {
                $this->warn('(Debug) Started: ' . now());
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'redesim-token' => $token,
            ])->post($url);

            $message = 'Resposta: ' . $response->body();

            if ($debug) {
                $this->warn('(Debug) body: ' . $message);
            }

            if ($response->failed()) {
                $message = 'Erro: ' . $response->status();
            }

            $redesimLogJob->update([
                'q191_ended' => now(),
                'q191_response' => $message,
            ]);

            if(strpos($message, 'Erro:')) {
                throw new RequestException($response);
            }

        } catch (Throwable $e) {
            if ($debug) {
                $this->error($e->getMessage());
                $this->error($e->getTraceAsString());
            } else {
                $this->error('An error occurred. Use --debug for more details.');
            }
            return self::FAILURE;
        }

        $this->info('Process completed successfully.');
        if ($debug) {
            $this->warn('(Debug) $message: ' . $message);
        }
        return self::SUCCESS;
    }
}
