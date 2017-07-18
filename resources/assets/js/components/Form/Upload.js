module.exports = {
  components: {
    Dropzone: require('vue2-dropzone')  //https://github.com/rowanwins/vue-dropzone
  },
  props: {
    url: {
      type: String,
      required: true
    },
    model: {
      type: String,
      required: true
    },
    collection: {
    	type: String,
      required: true
    },
    maxNumberOfFiles:{
      type: Number,
      required: false,
      default: 1
    },
    maxFileSizeInMb:{
      type: Number,
      required: false,
      default: 2
    },
    acceptedFileTypes: {
      type: String,
      required: false
    },
    thumbnailWidth: {
      type: Number,
      required: false,
      default: 200
    },
    uploadedImages : {
      type: Array,
      required: false,
      default: function () { return [] }
    },
  },
  data: function () { 
    return {
      mutableUploadedImages: this.uploadedImages
    }
  },
  template: `<dropzone :id="collection" 
                       :url="url" 
                       v-bind:preview-template="template"
                       v-on:vdropzone-success="onSuccess"
                       v-on:vdropzone-removed-file="onFileDelete"
                       v-on:vdropzone-thumbnail="onThumbGenerate"
                       v-on:vdropzone-file-added="onFileAdded"
                       :useFontAwesome="true" 
                       :ref="collection"
                       :maxNumberOfFiles="maxNumberOfFiles"
                       :maxFileSizeInMB="maxFileSizeInMb"
                       :acceptedFileTypes="acceptedFileTypes"
                       :thumbnailWidth="thumbnailWidth">
                
                <input type="hidden" name="model" :value="model">
                <input type="hidden" name="collection" :value="collection">
            </dropzone>`,
  mounted: function () { 
      this.$nextTick( () => {
        if(this.mutableUploadedImages) {
          _.each(this.mutableUploadedImages, (file, key) => {
            this.$refs[this.collection].manuallyAddFile({ name: file['name'], size: file['size'], type:  file['type']}, file['path']);
          });
        } 
      })
  },
  methods: {
    onSuccess: function (file, response) {
      console.log('A file was successfully uploaded')
    },

    onThumbGenerate: function(file) {
      //bug in dropzone
      this.placeIcon(file);
    },

    onFileAdded: function(file) {
      this.placeIcon(file);
    },

    onFileDelete: function (file, error, xhr) {
      //FIXME: bez jquery
      var deletedFilePath = $(file.previewElement).find('img').attr('src');

      if(deletedFilePath) {
        var deletedFileIndex = _.findIndex(this.mutableUploadedImages, {path: deletedFilePath});
        if(this.mutableUploadedImages[deletedFileIndex]) {
          this.mutableUploadedImages[deletedFileIndex]['deleted'] = true;
        }
      }
    },

    // onFileAdded: function(file) {
    //   console.log(file);
    //   if (file.type && !file.type.match(/image.*/)) {
    //     // This is not an image, so Dropzone doesn't create a thumbnail.
    //     // Set a default thumbnail:
    //     this.$refs[this.collection].dropzone.emit("thumbnail", file, "http://path/to/image");

    //     // You could of course generate another image yourself here,
    //     // and set it as a data url.
    //   }
    // },

    getFiles: function() {
      var files = this.mutableUploadedImages;

      _.each(this.$refs[this.collection].getAcceptedFiles(), (file, key) => {
        var response = JSON.parse(file.xhr.response);

        if(response.success) {
          files.push({
              collection: this.collection,
              name: file.name,
              width: file.width,
              height: file.height,
              model: this.model,
              path: response.path
          });
        }
      });

    	return files;
    },

    placeIcon: function(file) {
      //FIXME iconStyleString, nameStyleString
      var iconStyleString = 'width:'+this.thumbnailWidth+'px; height:'+this.thumbnailWidth+'px; font-size: '+this.thumbnailWidth/2+'px; line-height: '+this.thumbnailWidth+'px; text-align: center',
          nameStyleString =  'position: absolute;bottom: 0px;width: 100%;text-align: center;height: 20px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;font-size: 12px;line-height: 1.2;padding: 0 15px;';
          $previewElement = $(file.previewElement);

      if(file.type.includes('image')) {
        //nothing, default thumb
      }
      else if(file.type.includes('pdf')) {
        $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-pdf-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
      else if(file.type.includes('word')) {
         $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-word-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
      else if(file.type.includes('spreadsheet') || file.type.includes('csv')) {
        $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-excel-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
      else if(file.type.includes('presentation')) {
        $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-powerpoint-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
      else if(file.type.includes('video')) {
        $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-video-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
      else if(file.type.includes('text')) {
        $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-text-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
      else if(file.type.includes('zip') || file.type.includes('rar')) {
        $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-archive-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
      else {
        $previewElement.find('.dz-image').html('<i style="'+iconStyleString+'" class="fa fa-file-o"></i><p style="'+nameStyleString+'">'+file.name+'</p>');
      }
    },

    template: function() {
      return `
              <div class="dz-preview dz-file-preview">
                  <div class="dz-image" style="width: 200px;height: 200px" v-if="isImage">
                      <img data-dz-thumbnail />
                  </div>
                  <div class="dz-details">
                    <div class="dz-size"><span data-dz-size></span></div>
                    <div class="dz-filename"><span data-dz-name></span></div>
                  </div>
                  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                  <div class="dz-error-message"><span data-dz-errormessage></span></div>
                  <div class="dz-success-mark"><i class="fa fa-check"></i></div>
                  <div class="dz-error-mark"><i class="fa fa-close"></i></div>
              </div>
          `;
    }
  }
}