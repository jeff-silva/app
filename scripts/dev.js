const { exec } = require('child_process');
const path = require('path');

exec(
  "docker-compose up --build --force-recreate --remove-orphans --detach",
  {
    cwd: path.join(__dirname, '..'),
    detached: true,
  },
  (error, stdout, stderr) => {
    if (error) {
        console.log(`error: ${error.message}`);
        return;
    }
    if (stderr) {
        console.log(`stderr: ${stderr}`);
        return;
    }
    console.log(`stdout: ${stdout}`);
  }
);