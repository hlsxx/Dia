<template>
  <div :id="componentName" class="dia-row mb-3">
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nový záznam (<var class="color-primary">{{ tableName }}</var>)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="model-img modal-body text-center">
            <dia-form2 :params='{
              tableName: this.tableName,
              conditions: this.conditions,
              data: this.data
            }'></dia-form2>
          </div>
        </div>
      </div>
    </div>
    <!-- End modal -->

    <div class="row m-1">
      <div class="col-10 text-left">
        <h3 class="dia-row-title">{{ title }}</h3>
      </div>
      <div class="col-2 text-right float-right">
        <button data-toggle="modal" data-target="#exampleModalCenter" type="button" class="btn mb-2 mb-md-0 btn-primary btn-block"><span>Pridať záznam</span> 
          <div class="icon d-flex align-items-center justify-content-center">
            <i class="fas fa-plus"></i>
          </div>
        </button>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</template>

<script>
var diaRow = Object();

import diaForm from './Form2.vue';

export default {
  components: {
    'dia-form2': diaForm
  },
  props: ['params'],
  data() {
    return Object.assign(diaRow, {
      componentName: "row"
    });
  },
  beforeCreate() {
    diaRow = new Dia();
  },
  beforeMount() {
    f.setComponentParams(this);

    this.title = this.params['title'];
  }
}
</script>