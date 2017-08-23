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
                
                <input type="hidden" name="model" :value="model">
                <input type="hidden" name="collection" :value="collection">
            </dropzone>`,
  mounted: function () { 
    //FIXME: temporary ugly fix until is fixed in package https://github.com/rowanwins/vue-dropzone/issues/127
    $('head').append('<style>.dz-error-message { top: calc(100% + 10px) !important; left: calc(50% - 70px) !important; } .vue-dropzone .dz-preview .dz-error-mark { text-align: center; top: 25%!important;}</style>');
    
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

    onUploadError: function (file) {
      // if(file.xhr) {
      //   alert(JSON.parse(file.xhr.response));
      // }
    },

    onFileAdded: function(file) {
      this.placeIcon(file);
    },

    onFileDelete: function (file, error, xhr) {
      var deletedFileIndex = _.findIndex(this.mutableUploadedImages, {url: file.url});
      if(this.mutableUploadedImages[deletedFileIndex]) {
        this.mutableUploadedImages[deletedFileIndex]['deleted'] = true;
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
                                                        }, file['thumb_url'], 
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
      var files = this.mutableUploadedImages;

      _.each(this.$refs[this.collection].getAcceptedFiles(), (file, key) => {
        var response = JSON.parse(file.xhr.response);

        if(response.path) {
          files.push({
              collection: this.collection,
              name: file.name,  //TODO: editable in the future
              file_name: file.name,
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
      //FIXME iconStyleString, nameStyleString, linkStyleString
      //FIXME cele to je jqueryoidne, asi si budeme musiet spravit vlastny vue wrapper, tento je zbugovany
      var iconStyleString = 'width:'+this.thumbnailWidth+'px; height:'+this.thumbnailWidth+'px; font-size: '+this.thumbnailWidth/2+'px; line-height: '+this.thumbnailWidth+'px; text-align: center',
          nameStyleString =  'position: absolute;bottom: 0px;width: 100%;text-align: center;height: 20px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;font-size: 12px;line-height: 1.2;padding: 0 15px;';
          linkStyleString = 'cursor: pointer;color:white;',
          $previewElement = $(file.previewElement);

      if(file.url) {
        $previewElement.find('.dz-filename').html('<a href="'+file.url+'" target="_BLANK" style="'+linkStyleString+'" class="dz-btn dz-custom-download">'+file.name+'</a>');
      }

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
                  <div class="dz-image" style="width: 200px;height: 200px">
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
