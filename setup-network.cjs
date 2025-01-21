const os = require('os');
const fs = require('fs');
const path = require('path');
const qrcode = require('qrcode-terminal');
require('dotenv').config();

const app_code = process.env.APP_CODE;

function getLocalIp() {
  const networkInterfaces = os.networkInterfaces();
  for (const interfaceName in networkInterfaces) {
    for (const iface of networkInterfaces[interfaceName]) {
      if (iface.family === 'IPv4' && !iface.internal) {
        return iface.address;
      }
    }
  }
  return '127.0.0.1'; // Default fallback
}

function generateAppCode() {
  if (app_code) {
    return app_code;
  }
  return Math.random().toString(36).substring(2, 6);
}

function writeEnvFile(variables) {
  const envPath = path.resolve('.env');
  const content = fs.existsSync(envPath) ? fs.readFileSync(envPath, 'utf8') : '';

  let newContent = content;
  for (const [key, value] of Object.entries(variables)) {
    const regex = new RegExp(`^${key}=.*`, 'm');
    if (regex.test(newContent)) {
      newContent = newContent.replace(regex, `${key}=${value}`);
    } else {
      newContent += `\n${key}=${value}`;
    }
  }

  fs.writeFileSync(envPath, newContent, 'utf8');
  console.log(`Updated .env with variables:`, variables);

  // Tampilkan QR Code untuk APP_URL
  const appUrl = variables.APP_URL;
  console.log(`\nScan this QR Code to access the app:`);
  qrcode.generate(appUrl, { small: true }); // QR Code dalam ukuran kecil
}

const localIp = getLocalIp();
const port = 8000; // Default port for Laravel

writeEnvFile({
  APP_URL: `http://${localIp}:${port}`,
  APP_CODE: generateAppCode(),
  HOST: localIp,
  PORT: port,
  VITE_DEV_SERVER_HOST: localIp,
});

console.log(`Setup complete: APP_URL=http://${localIp}:${port}`);
