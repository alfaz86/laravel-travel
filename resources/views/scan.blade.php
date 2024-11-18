<!DOCTYPE html>
<html>
<head>
  <title>QR Code</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 360px;
      margin: 0 auto;
    }

    #qrcode, img {
      text-align: center;
    }
  </style>
</head>
<body>
  <h1>QR Code URL</h1>
  <div id="qrcode"></div>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
  <script>
    new QRCode(document.getElementById("qrcode"), {
      text: "{{ env('APP_URL', 'http://localhost') }}",
      width: 256,
      height: 256,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H
    });
  </script>
</body>
</html>