<template>
  <div>
    {{ tableName }}
    <form 
      id='dia-dropzone' 
      action="index.php?action=dia_upload" 
      class="dropzone">
    </form>

    <button 
      @click="removeAllFiles()" 
      class="btn btn-danger"
    >Remove all</button>
  </div>
</template>

<script>
  Dropzone.autoDiscover = false;

  export default {
    props: {
      tableName: {
        type: String,
      },
      conditions: {
        type: Object
      }
    },
    data() {
      return {
        uploadedFiles: []
      }
    },
    methods: {
      action(params) {
        if (this.tableName == params.tableName) {
          axios.post('index.php?action=dia_select', {
            params: {
              tableName: params.tableName,
              conditions: params.conditions
            }
          }).then((res) => {
            if (res.data.status != 'fail') {
              this.hideAllFiles();
              
              var _this = this;
              // LOAD DATA TO DROPZONE
              var thisDropzone = this.dropzone;

              setTimeout(() => {
                console.log(this.fileDir);
                $.each(res.data, function(key, value){
                  _this.uploadedFiles.push(value.id);

                  var file = { name: value.filename, size: value.size };
                  var name = value.filename;

                  thisDropzone.options.addedfile.call(thisDropzone, file);
                  if (name.includes('.jpg') || name.includes('.png') || name.includes('.jpeg') || name.includes('.gif')) {
                    thisDropzone.options.thumbnail.call(thisDropzone, file, 'Files/dropzone/' + value.filename);
                  }
                  thisDropzone.options.complete.call(thisDropzone, file);
                  thisDropzone.options.processing.call(thisDropzone, file);
                });
              }, 300);
              
             
            } else {

            }
          })
        }
      },
      hideAllFiles() {
        this.dropzone.removeAllFiles();

        // Delete loaded files html
        $('#dia-dropzone .dz-preview').remove();
        $('.dropzone').removeClass('dz-started');
      },
      removeAllFiles() {
        axios.post(
          'index.php?action=dia_delete',
          {
            tableName: this.tableName,
            id: this.uploadedFiles
          }
        ).then(() => {
          this.hideAllFiles();
        })
      },
    },
    mounted() {
      emitter.on("emitActionDropzone", params => {
        this.action(params);
      });

      var _this = this;

      this.dropzone = new Dropzone("#dia-dropzone", {
       init: function() {
          var thisDropzone = this;
          axios.post('index.php?action=dia_select', {
            params: {
              tableName: _this.tableName,
              conditions: _this.conditions
            }
          }).then((res) => {
            $.each(res.data['data'], function(key, value){
              _this.uploadedFiles.push(value.id);

              var file = { name: value.filename, size: value.size };
              var name = value.filename;

              thisDropzone.options.addedfile.call(thisDropzone, file);
              if (name.includes('.jpg') || name.includes('.png') || name.includes('.jpeg') || name.includes('.gif')) {
                thisDropzone.options.thumbnail.call(thisDropzone, file, 'Files/' + _this.tableName + '/' + value.filename);
              }
              thisDropzone.options.complete.call(thisDropzone, file);
              thisDropzone.options.processing.call(thisDropzone, file);
            });
          })
        }
      });

     this.dropzone.on("addedfile", file => {
        var i = 0;
        var uploadFile = true;

        $('#dia-dropzone .dz-filename').each(function(index, obj){
          if ($(obj).text() == file.name) {
            i++;
            if (i == 2) {
              uploadFile = false;
              alert('Tento dokument sa tu už nachádza');
              _this.dropzone.removeFile(file);
              $('.dropzone').addClass('dz-started');
              i = 0;
            }
          }
        });

        if (uploadFile == true) {
          axios.post('index.php?action=dia_insert', {
            'tableName': this.tableName,
            'data': {
              'user_id': 1,
              'test_id': 1,
              'filename': file.name,
              'size': file.size
            }
          }).then((res) => {
            // Res return item ID and then push into array
            this.uploadedFiles.push(res.data['id']);
          })
        }
      });
    }
  }
</script>
