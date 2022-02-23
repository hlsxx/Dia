<template>
  <div>

    <!-- Modal -->    
    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="model-img modal-body text-center">
            <img :src="'http://localhost/holes/dia/files/products/' + itemEdit['image']" :alt="itemEdit['image']">
          </div>
        </div>
      </div>
    </div>

    <div v-if="data.length > 0" class="row">
      <div 
        v-for="item in data" :key="item" 
        class="col-lg-3 col-md-4 col-xs-6 thumb"
        >
        <div @click="edit(item)" data-toggle="modal" data-target="#exampleModalCenter" class="fancybox" rel="ligthbox">
          <div class="dia-image">
            <img :src="'http://localhost/holes/dia/files/products/' + item['image']" class="zoom img-fluid"  :alt="item['image']">
            <button 
              @click="itemDelete(item.id)"
              class="btn btn-danger btn-image-delete text-center"
            >
              <i class="fas fa-trash-alt color-red-dark"/>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <h5 class="text-center">Žiadne obrázky</h5>
    </div>

    <div>
      <dia-file-uploader :params='{
        tableName: this.tableName,
        uploadAction: this.uploadAction
      }'></dia-file-uploader>
    </div>

  </div>
</template>

<script>
import fileUploader from './FileUploader5.vue';

export default {
  components: {
    'dia-file-uploader': fileUploader
  },
  props: ['params'],
  data() {
    return Object.assign(dia, {
      itemEdit: [],
      uploadAction: "upload_gallery_image"
    });
  },
  methods: {
    edit(item) {
      this.itemEdit = item;
    },
    itemDelete(itemId) {
      window.event.cancelBubble = true;
      
      dia.itemDelete(
        this.tableName, 
        itemId,
        function () {
          window.location.reload();
        }
      );
    }
  },
  mounted() {
    f.setComponentParams(this);
    dia.setComponentData(this);

    //this.uploadAction = this.params['uploadAction'];
  }
}
</script>
