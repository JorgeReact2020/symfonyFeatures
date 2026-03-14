import { startStimulusApp } from '@symfony/stimulus-bundle';

const app = startStimulusApp();

// Debug: log when Stimulus app is ready
console.log('Stimulus app started:', app);

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
