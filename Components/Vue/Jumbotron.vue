<template>
  <div class="jumbotron m-5">
    <h1 v-if="params.title" class="display-4">{{ params.title }}</h1>
    <p v-if="params.topText" class="lead">{{ params.topText }}</p>
    <hr class="my-4">
    <p v-if="params.body">{{ params.body }}</p>
    <p v-if="params.link" class="lead">
      <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
    </p>
  </div>
</template>

<script>
export default {
  props: ['params']
}
</script>