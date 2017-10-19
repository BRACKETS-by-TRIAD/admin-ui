import dropzone from 'vue2-dropzone';

const BaseUpload = {
  components: {
    Dropzone: dropzone,
  },
  props: {
    url: {
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
      mutableUploadedImages: this.uploadedImages,
      headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }
  },
  template: `<dropzone :id="collection" 
                       :url="url" 
                       v-bind:preview-template="template"
                       v-on:vdropzone-success="onSuccess"
                       v-on:vdropzone-error="onUploadError"
                       v-on:vdropzone-removed-file="onFileDelete"
                       v-on:vdropzone-file-added="onFileAdded"
                       :useFontAwesome="true" 
                       :ref="collection"
                       :maxNumberOfFiles="maxNumberOfFiles"
                       :maxFileSizeInMB="maxFileSizeInMb"
                       :acceptedFileTypes="acceptedFileTypes"
                       :thumbnailWidth="thumbnailWidth"
                       :headers="headers">
                
                <input type="hidden" name="collection" :value="collection">
            </dropzone>`,
  mounted: function () {     
    this.attachAlreadyUploadedMedia();
  },
  methods: {
    onSuccess: function (file, response) {
      if(!file.type.includes('image')) {
        setTimeout(function() {
            //FIXME jquery
            $(file.previewElement).removeClass('dz-file-preview');
        }, 3000);
      }
    },

    onUploadError: function (file, error) {
      let errorMessage = typeof error == 'string' ? error : error.message;
      this.$notify({ type: 'error', title: 'Error!', text: errorMessage});
      $(file.previewElement).find('.dz-error-message span').text(errorMessage);
    },

    onFileAdded: function(file) {
      this.placeIcon(file);
    },

    onFileDelete: function (file, error, xhr) {
      var deletedFileIndex = _.findIndex(this.mutableUploadedImages, {url: file.url});
      if(this.mutableUploadedImages[deletedFileIndex]) {
        this.mutableUploadedImages[deletedFileIndex]['deleted'] = true;

        //dontSubstractMaxFiles fix
        var currentMax = this.$refs[this.collection].dropzone.options.maxFiles;
        this.$refs[this.collection].setOption('maxFiles', currentMax + 1);
      }
    },

    attachAlreadyUploadedMedia: function() {
      this.$nextTick( () => {
        if(this.mutableUploadedImages) {
          _.each(this.mutableUploadedImages, (file, key) => {

            this.$refs[this.collection].manuallyAddFile({ name: file['name'], 
                                                          size: file['size'], 
                                                          type: file['type'], 
                                                          url: file['url'],
                                                        }, 
                                                        file['thumb_url'], 
                                                        false,
                                                        false,
                                                        {
                                                          dontSubstractMaxFiles: false,
                                                          addToFiles: true
                                                        });
          });
        } 
      })
    },

    getFiles: function() {
      var files = []; 

      _.each(this.mutableUploadedImages, (file, key) => {
        if(file.deleted) {
          files.push({
              id: file.id,
              collection_name: this.collection,
              action: 'delete',
          });
        }
      });

      _.each(this.$refs[this.collection].getAcceptedFiles(), (file, key) => {
        var response = JSON.parse(file.xhr.response);

        if(response.path) {
          files.push({
              id: file.id,
              collection_name: this.collection,
              path: response.path,
              action: file.deleted ? 'delete' : 'add', //TODO: update ie. meta_data.name
              meta_data: {
                name: file.name,  //TODO: editable in the future
                file_name: file.name,
                width: file.width,
                height: file.height,
              }
          });
        }
      });

    	return files;
    },

    placeIcon: function(file) {
      //FIXME cele to je jqueryoidne, asi si budeme musiet spravit vlastny vue wrapper, tento je zbugovany
      var $previewElement = $(file.previewElement);

      if(file.url) {
        $previewElement.find('.dz-filename').html('<a href="'+file.url+'" target="_BLANK" class="dz-btn dz-custom-download">'+file.name+'</a>');
      }

      if(file.type.includes('image')) {
        //nothing, default thumb
      }
      else if(file.type.includes('pdf')) {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-pdf-o"></i><p>'+file.name+'</p>');
      }
      else if(file.type.includes('word')) {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-word-o"></i><p>'+file.name+'</p>');
      }
      else if(file.type.includes('spreadsheet') || file.type.includes('csv')) {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-excel-o"></i><p>'+file.name+'</p>');
      }
      else if(file.type.includes('presentation')) {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-powerpoint-o"></i><p>'+file.name+'</p>');
      }
      else if(file.type.includes('video')) {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-video-o"></i><p>'+file.name+'</p>');
      }
      else if(file.type.includes('text')) {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-text-o"></i><p>'+file.name+'</p>');
      }
      else if(file.type.includes('zip') || file.type.includes('rar')) {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-archive-o"></i><p>'+file.name+'</p>');
      }
      else {
        $previewElement.find('.dz-image').html('<i class="fa fa-file-o"></i><p>'+file.name+'</p>');
      }
    },

    template: function() {
      return `
              <div class="dz-preview dz-file-preview">
                  <div class="dz-image">
                      <img data-dz-thumbnail />
                  </div>
                  <div class="dz-details">
                    <div class="dz-size"><span data-dz-size></span></div>
                    <div class="dz-filename"></div>
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

export default BaseUpload;
