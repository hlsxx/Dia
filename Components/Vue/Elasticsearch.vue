<template>
  <div v-if="index != 'offline'">
    <div class="form-inline">
      <input 
        class="form-control col" 
        type="search" 
        placeholder="Search" 
        aria-label="Search"
        v-model="search"
      />
      <template v-if="filteredSearch.length > 0">
        <i @click="cleanSearch()" class="fas fa-hand-sparkles col" style="cursor:pointer"/>
      </template>
    </div>
    <div class="search-div">
      <ul v-for="hit in filteredSearch" :key="hit._id" class="list-group">
        <li @click="openUrl(hit.link)" class="list-group-item d-flex justify-content-between align-items-center">
          <var class="color-primary mr-2">{{ hit.hit }}</var>
          <span class="badge badge-primary badge-pill bg-primary"> {{ hit.id }}</span>
        </li>
      </ul>
    </div>
  </div>
  <div v-else>
    <p class='border border-danger text-center mt-5'>
      <span class='text-danger'>
        Elasticsearch not running
        <button
          @click="showConnectionInfo = !showConnectionInfo" 
          class="btn btn-warning"
        >ⓘ</button>
      </span>
    </p>
    <div v-if="showConnectionInfo">
      <div class="card">
        <div class="card-body">
          <p>localhost:9200 not running</p>
          <p>Start your Elasticsearch server</p>
          <p>Linux: cd /elastic_folder/</p>
          <p>./bin/elasticsearch</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      search: "",
      count: 0,
      hits: [],
      showConnectionInfo: false
    }
  },
  props: ['index', 'searchFields'],
  watch: {
    search(val) {
      axios.post('index.php?action=dia_elasticsearch_get', {
        search: val,
        index: this.index,
        searchFields: this.searchFields
      }).then((res) => {
        this.count = res.data['count'];
        this.hits = res.data['hits']
      })
    }
  },
  computed: {
    filteredSearch() {
      return this.hits.map(
        function(hit) { 
          return { 
            hit: "" + hit._source.title + "",
            link: hit._source.link,
            id: hit._id 
          } 
        }
      );
    }
  },
  methods: {
    openUrl(link) {
      window.location.href = link;
    },
    cleanSearch() {
      this.search = "";
    }
  }
}
</script>