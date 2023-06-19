$(document).ready(function() {
    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    var browseButton = document.getElementById('browseButton');

    $("#closePopup").click(function() {
      $(".overlay").removeClass("active");
      $(".popup").fadeOut();
    });

    function popup(title, content) {
      $(".overlay").addClass("active");
      $(".popup").fadeIn();
      $("#popupTitle").text(title);
      $("#popupContent").html(content);
    }

    function cutString(text) {
      if (text.length > 31) {
        return text.substring(0, 31) + "...";
      }
      return text;
    }


    dropZone.addEventListener('dragover', handleDragOver, false);
    dropZone.addEventListener('dragleave', handleDragLeave, false);
    dropZone.addEventListener('drop', handleFileSelect, false);
    fileInput.addEventListener('change', handleFileSelect, false);
    browseButton.addEventListener('click', function() {
      fileInput.click();
    });

    function handleDragOver(event) {
      event.stopPropagation();
      event.preventDefault();
      dropZone.classList.add('bg-gray-300');
    }

    function handleDragLeave(event) {
      event.stopPropagation();
      event.preventDefault();
      dropZone.classList.remove('bg-gray-300');
    }

    function handleFileSelect(event) {
      event.stopPropagation();
      event.preventDefault();
      dropZone.classList.remove('bg-gray-300');

      var file;
      if (event.type === 'drop') {
        file = event.dataTransfer.files[0];
      } else if (event.type === 'change') {
        file = event.target.files[0];
      }

      if (file) {
        $("#selectedFile").text(cutString(file.name));
        dropZone.classList.add('bg-gray-200');
      }
    }


    $('#uploadButton').click(function() {
      var file = document.getElementById('fileInput').files[0];
      if (file) {
        var formData = new FormData();
        formData.append('file', file);

        // Show loading indicator
        var loadingLine = $('<div class="p-2 mt-5 border-r-8 bg-blue-500 h-1"></div>');
        $('.max-w-md').append(loadingLine);

        $.ajax({
          url: 'upload.php', // Replace with the URL to your PHP file for handling the upload
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          xhr: function() {
            var xhr = new window.XMLHttpRequest();

            // Add progress event listener
            xhr.upload.addEventListener('progress', function(event) {
              if (event.lengthComputable) {
                var percent = (event.loaded / event.total) * 100;
                // Update loading indicator width
                loadingLine.css('width', percent + '%');
              }
            }, false);

            return xhr;
          },
          success: function(response) {
            if (response !== "Error") {
              // Clean-up
              loadingLine.remove();
              $("#selectedFile").text("");
              dropZone.classList.remove('bg-gray-200');
              
              // Handle the success response from the server
              popup("This is your Files Link.", "<a href='" + response + "'>" + response + "</a>")
              console.log('Upload successful:', response);
            } else {
              loadingLine.remove();
              $("#selectedFile").text("");
              dropZone.classList.remove('bg-gray-200');
              
              popup("There was an error uploading this file.", "Try Using <a href='https://ftl.rf.gd'>FTL V1.0</a>")
              console.log('Upload failed:', response);
            }
          },
          error: function(xhr, status, error) {
            // Clean-up
            loadingLine.remove();
            $("#selectedFile").text("");
            dropZone.classList.remove('bg-gray-200');

            console.log('Upload failed:', error);
          }
        });
      }
    });


  });