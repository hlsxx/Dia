<template>
  <div>
    <slot>{{ html }}</slot>
  </div>
</template>

<script>
export default {
  props: ['html']
}
</script>