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
      required: false,
      default: '*'
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
                       v-on:vdropzone-success="onSuccess"
                       v-on:vdropzone-removed-file="onFileDelete"
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
            this.$refs[this.collection].manuallyAddFile({ name: file['name'], size: file['size'] }, file['path']);
          });
        } 
      })
  },
  methods: {
    onSuccess: function (file, response) {
      console.log('A file was successfully uploaded')
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
  }
}