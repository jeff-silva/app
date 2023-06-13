const { spawn } = require('child_process');
const path = require('path');

const dockerCompose = spawn(
  'docker-compose',
  [
    'up',
    '--build',
    '--force-recreate',
    '--remove-orphans',
  ],
  {
    cwd: path.join(__dirname, '..'),
  }
);

dockerCompose.stdout.on('data', (data) => {
  console.log(`${data}`);
});

dockerCompose.stderr.on('data', (data) => {
  console.error(`${data}`);
});

dockerCompose.on('error', (error) => {
  console.error(`${error.message}`);
});

dockerCompose.on('close', (code) => {
  console.log(`child process exited with code ${code}`);
});

process.on('SIGINT', async () => {
  await dockerCompose.kill('SIGINT');
  // process.exit();
});