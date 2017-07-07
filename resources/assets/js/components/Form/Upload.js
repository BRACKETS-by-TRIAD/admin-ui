module.exports = {
  components: {
    Dropzone: require('vue2-dropzone')  //https://github.com/rowanwins/vue-dropzone
  },
  props: {
    url: {
      type: String,
      required: true
    },
    collection: {
    	type: String,
      required: true,
      default: 'vueDropzoneUpload'
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
                
                <!-- Optional parameters if any! -->
                <input type="hidden" name="token" value="xxx">
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

    getFiles: function() {
      var files = this.mutableUploadedImages;

      _.each(this.$refs[this.collection].getAcceptedFiles(), (file, key) => {
        var response = JSON.parse(file.xhr.response);

        if(response.success) {
          files.push({
              collection: this.collection,
              path: response.data.original_filepath
          });
        }
      });

    	return files;
    },
  }
}