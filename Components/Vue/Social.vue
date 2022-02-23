<template>
   <ul class='list-group list-group-flush'>
    <li v-for='item in data' :key='item.id' class='list-group-item d-flex justify-content-between align-items-center flex-wrap'>
      <h6 class='mb-0'>
        <i class="fab fa-facebook"></i>
        {{ item.title }}
      </h6>
      <span class='text-secondary'>{{ item.description }}</span>
    </li>
  </ul>
</template>

<script>
  export default {
    props: ['params'],
    data() {
      return {
        data: [],
        tableName: "",
        conditions: []
      }
    },
    methods: {
      loadData() {
        axios.post('index.php?action=dia_select', {
          params: {
            tableName: this.tableName,
            conditions: this.conditions
          }
        }).then((res) => {
          if (res.data.status != 'fail') {
            this.data = res.data;
          }
        })
      }
    },
    mounted() {
      this.tableName = this.params['tableName'];
      this.conditions = this.params['conditions'];

      if (this.params['data'].length > 0) {
        this.data = this.params['data'];
      } else {
        this.loadData();
      }
    }
  }
</script>