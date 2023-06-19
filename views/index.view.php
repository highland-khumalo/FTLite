<body class="flex items-center justify-center min-h-screen">
  <div class="popup">
    <h2 class="text-lg font-bold mb-4" id="popupTitle"></h2>
    <p id="popupContent"></p>
    <button id="closePopup" class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
      Close Popup
    </button>
  </div>
  <div class="max-w-md w-full bg-white p-8 rounded">
    <div class="text-center mb-6">
      <img src="./logo-transparent.png" alt="FTLite logo transparent" class="mx-auto">
    </div>
    <div class="mb-4" id="dropZone">
      <div class="border-2 border-gray-300 border-dashed rounded-lg p-6 cursor-pointer">
        <div class="text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4a1 1 0 00-1 1v2a1 1 0 002 0V5a1 1 0 00-1-1zM15 4a1 1 0 00-1 1v2a1 1 0 002 0V5a1 1 0 00-1-1z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 20H3a2 2 0 01-2-2V6a2 2 0 012-2h18a2 2 0 012 2v12a2 2 0 01-2 2z" />
          </svg>
          <div class="mt-1 text-sm text-gray-600">
            <p>Drag and drop your file here or</p>
            <p class="text-blue-500 cursor-pointer" id="browseButton">Browse</p>
            <p class="cursor-pointer" id="selectedFile"></p>
          </div>
        </div>
        <input type="file" id="fileInput" class="hidden">
      </div>
    </div>
    <div class="flex justify-center">
      <button id="uploadButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Upload
      </button>
    </div>
  </div>

  <!-- JS -->
  <script src="./src/js/main.js"></script>
</body>

</html>