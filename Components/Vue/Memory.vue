<template>
  <div id='memory'>
    <ul class="tags">
      <li @click="clearMemory()" class='mr-2 onclick' style='color:#9c9c9c'>
        <i class="fas fa-hand-sparkles"></i>
      </li>
      <li v-for='item in data' :key='item.url'><a :href="item" class="tag" :class="classObject(item)">{{ item }}</a></li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: {
      params: {
        type: Object
      }
    },
    data() {
      return {
        data: [],
        tableName: "",
        conditions: [],
        currentPage: ""
      }
    },
    methods: {
      loadData() {
        axios.post('index.php?action=dia_memory_load')
        .then((res) => {
          if (res.data.status != 'fail') {
            this.data = res.data.pages;
            this.currentPage = res.data.currentPage;
          }
        })
      },
      classObject(item) {
        return {
          'tag-active': this.currentPage == item
        }
      },
      clearMemory() {
        axios.post('index.php?action=dia_memory_delete')
        .then((res) => {
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
