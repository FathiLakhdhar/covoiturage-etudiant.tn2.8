/**
 * Created by Toshiba on 23-04-2016.
 */



function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if ( !f.type.match('image.jpeg') && !f.type.match('image.png')) {
            continue;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                // Render thumbnail.
                $('.p_img_ph').attr('src',e.target.result);
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}


$(document).ready(
    function(){
        var inputfile= document.getElementById('wanasni_photobundle_photo_file');
       if(inputfile){
           inputfile.addEventListener('change', handleFileSelect, false);
       }

    }
);