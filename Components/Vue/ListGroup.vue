<template>
  <ul 
    v-for='item in data' :key='item.id' 
    class='list-group'
  >
    <a :href="item[url]">
      <li
        class='list-group-item d-flex justify-content-between align-items-center action-hover'
      >
        {{ item[title] }}
        <span class='badge badge-primary badge-pill'>{{ item[bubble] }}</span>
      </li>
    </a>
  </ul>
</template>

<script>
export default {
  props: ['params'],
  data() {
    return {
      data: [],
      title: '',
      bubble: '',
      url: ''
    }
  },
  mounted() {
    this.data = this.params['data'];
    this.title = this.params['title'];
    this.bubble = this.params['bubble'];
    this.url = this.params['url'];
  }
}

</script>
