module.exports = {
  apps : [{
    name: 'app',
    script: './lib/index.js',
    watch: true,
    ignore_watch : ['node_modules', 'docker-data'],
  }]
};
