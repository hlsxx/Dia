<template>
  <canvas 
    :id="params['id']" 
    width="auto" 
    height="auto">
  </canvas>
</template>

<script>
export default {
  props: ['params'],
  data() {
    return {
      backgroundColor: [],
      borderColor: [],
      type: '',
      label: '',
      labels: [],
      data: [],
      borderWidth: 1
    }
  },
  methods: {
    dynamicColor() {
      var r = Math.floor(Math.random() * 255);
      var g = Math.floor(Math.random() * 255);
      var b = Math.floor(Math.random() * 255);

      return {
        background: "rgba(" + r + "," + g + "," + b + ", 0.5)",
        border:  "rgba(" + r + "," + g + "," + b + ", 1)",
      }
    }
  },
  beforeMount() {
    this.params['data'].forEach(() => {
      var colors = this.dynamicColor();
      this.backgroundColor.push(colors.background);
      this.borderColor.push(colors.border);
    })
  },
  mounted() {
    f.setComponentParams(this);

    var ctx = $('#' + this.params['id']);

    new Chart(ctx, {
      type: this.type,
      data: {
        labels: this.labels,
        datasets: [{
          label: this.label,
          data: this.data,
          backgroundColor: this.backgroundColor,
          borderColor: this.borderColor,
          borderWidth: this.borderWidth
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  },
}
</script>