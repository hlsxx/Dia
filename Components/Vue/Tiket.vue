<template>
  <ul v-for='item in data' :key='item.id' class='list-group mb-3'>
    <li class='list-group-item d-flex justify-content-between bg-light'>
      <div class='text-success'>
        <h6 class='my-0'>{{ item[title] }}</h6>
        <small>{{ item[desc] }}</small>
      </div>
      <span class='text-success'>-$5</span>
    </li>
  </ul>
</template>

<script>
  export default {
    props: ['params'],
    data() {
      return {
        data: [],
        title: '',
        desc: ''
      }
    },
    mounted() {
      this.data = this.params['data'];
      this.title = this.params['title'];
      this.desc = this.params['desc'];
      //console.log(this.data);
    }
  }

</script>