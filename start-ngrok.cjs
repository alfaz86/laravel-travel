const fs = require('fs');
const path = require('path');
const axios = require('axios');
const ngrok = require('ngrok');
require('dotenv').config();

const app_code = process.env.APP_CODE;
const jsonServerUrl = process.env.JSON_SERVER_URL;

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
}

async function startNgrok() {
  try {
    const url = await ngrok.connect({
      addr: `${process.env.HOST}:${process.env.PORT}`,
    });
    console.log(`Ngrok is running. Public URL: ${url}`);
    return url;
  } catch (error) {
    console.error('Failed to start Ngrok:', error.message);
    return null;
  }
}

async function sendDataToJsonServer(ngrokUrl) {
  const redirectData = {
    app_code,
    date: new Date().toISOString().split('T')[0],
    redirect_url: `${ngrokUrl}/api/payment/redirect`,
    callback_url: `${ngrokUrl}/api/payment/callback`,
  };

  try {
    const postResponse = await axios.post(jsonServerUrl, redirectData);
    console.log('Data successfully sent to JSON Server:', postResponse.data);
  } catch (error) {
    console.error('Error while checking or sending data to JSON Server:', error.message);
  }
}

(async () => {
  const ngrokUrl = await startNgrok();

  if (ngrokUrl) {
    writeEnvFile({
      NGROK_URL: ngrokUrl,
    });
    await sendDataToJsonServer(ngrokUrl);
  }
})();
