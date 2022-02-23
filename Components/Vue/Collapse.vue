<template>
  <div id="accordion">
    <div v-for="item in data" :key="item.id">
      <div class='card'>
        <div class='card-header' :id="'heading_' + item.id">
          <h5 class='mb-0'>
            <button class='btn btn-link' data-toggle='collapse' :data-target="'#collapse_' + item.id" aria-expanded='false' :aria-controls="'collapse_' + item.id">
              {{ item[title] }}
            </button>
          </h5>
        </div>
        
        <div :id="'collapse_' + item.id" class='collapse' :aria-labelledby="'heading_' + item.id" data-parent='#accordion'>
          <div class='card-body'>
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['params'],
    data() {
      return {
        data: [],
        title: ''
      }
    },
    mounted() {
      this.data = this.params['data'];
      this.title = this.params['title'];
    }
  }
</script>
