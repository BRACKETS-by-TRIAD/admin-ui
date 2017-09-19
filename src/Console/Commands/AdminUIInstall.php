<?php namespace Brackets\AdminUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class AdminUIInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-ui:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a brackets/admin-ui package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $files)
    {
        $this->info('Installing package brackets/admin-ui');

        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Admin\\AdminServiceProvider",
        ]);

        $this->frontendAdjustments($files);

        $this->info('Package brackets/admin-ui installed');
    }

    private function strReplaceInFile($fileName, $ifExistsRegex, $find, $replaceWith) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return;
        }

        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }

    private function appendIfNotExists($fileName, $ifExistsRegex, $append) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return;
        }

        return File::put($fileName, $content.$append);
    }

    /**
     * @param Filesystem $files
     */
    private function frontendAdjustments(Filesystem $files) {
        // webpack
        if ($this->appendIfNotExists('webpack.mix.js', '|vendor/brackets/admin|', "\n\n" . $files->get(__DIR__ . '/../../../install-stubs/webpack.mix.js'))) {
            $this->info('Webpack configuration updated');
        }

        //Change package.json
        $this->info('Changing package.json');
        $packageJsonFile = base_path('package.json');
        $packageJson = $files->get($packageJsonFile);
        $packageJsonContent = json_decode($packageJson, JSON_OBJECT_AS_ARRAY);
        $packageJsonContent['devDependencies']['vee-validate'] = '^2.0.0-rc.13';
        $packageJsonContent['devDependencies']['vue'] = '^2.3.4';
        $packageJsonContent['devDependencies']['vue-flatpickr-component'] = '^2.4.1';
        $packageJsonContent['devDependencies']['vue-js-modal'] = '^1.2.8';
        $packageJsonContent['devDependencies']['vue-multiselect'] = '^2.0.2';
        $packageJsonContent['devDependencies']['vue-notification'] = '^1.3.2';
        $packageJsonContent['devDependencies']['vue-quill-editor'] = '^2.3.0';
        $packageJsonContent['devDependencies']['moment'] = '^2.18.1';
        $packageJsonContent['devDependencies']['vue2-dropzone'] = '^2.3.5';
        $packageJsonContent['devDependencies']['vue-cookie'] = '^1.1.4';
        $files->put($packageJsonFile, json_encode($packageJsonContent, JSON_PRETTY_PRINT));
        $this->info('package.json changed');
    }
}