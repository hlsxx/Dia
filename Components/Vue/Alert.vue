<template>
  <div class="alert mt-5 mb-5" :class="[alertType]" role="alert">
    <button 
      type="button" 
      class="close" 
      data-dismiss="alert" 
      aria-label="Close"
      @click="deleteAlert()"
    ><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="alert-heading">{{ title }}</h4>
    <p>{{ text }}</p>
    <hr>
    <p class="mb-0">{{ footerText }}</p>
  </div>
</template>
<script>
export default {
  props: [
    'text', 
    'title', 
    'footerText',
    'alertId',
    'alertType',
    'type'
  ],
  methods: {
    deleteAlert() {
      if (this.type == 3) {
        var _this = this;
        swal({
          title: "Ste si istý?",
          text: "Naozaj chcete vymazať oznámenie?",
          type: "warning",
          showCancelButton: true,
          cancelButtonClass: "btn btn-secondary",
          confirmButtonClass: "btn btn-danger",
          confirmButtonText: "Áno",
          cancelButtonText: "Nie",
          closeOnConfirm: false,
          closeOnCancel: false,
        },
        function(isConfirm) {
          if (isConfirm) {
            _this.deleteAlertAxios(),
            swal({
              title: "Vymazané",
              text: "Oznámenie bolo vymazané",
              type: "success"
            })
          } else {
            swal("Zrušené", "Onzámenie nebolo vymazané", "warning") 
          }
        });
      } else {
        this.deleteAlertAxios();
      }
    
    },

    deleteAlertAxios() {
      axios.post(
        'index.php?action=dia_delete',
        {
          tableName: 'dia_alerts',
          id: this.alertId
        }
      );
    }
  },
}
</script>