const ExtractTextPlugin = require('extract-text-webpack-plugin');
const path = require('path');
const glob = require('glob');
const fs = require('fs-extra');

const relativePath = '../../..';
const root = path.join(__dirname, relativePath);

fs.copySync(
  path.join(root, 'resource/asset/admin/vendor/'),
  path.join(root, 'public/asset/admin/vendor/')
);

fs.copySync(
  path.join(root, 'resource/asset/admin/img/'),
  path.join(root, 'public/asset/admin/img/')
);

const entry = {
  'asset/admin/js/vendor': ['whatwg-fetch']
};
glob.sync(path.join(root, './resource/asset/admin/js/app/**/*.js')).forEach((file) => {
  const output = file
    .replace(`${root}/resource/asset/admin/js/app/`, '')
    .replace(/^\/|\/$/g, '')
    .replace(/\.[^.]*$/, '');

  entry[`asset/admin/js/${output}`] = file;
});

glob.sync(path.join(root, './resource/asset/admin/scss/app/**/*.scss')).forEach((file) => {
  const output = file
    .replace(`${root}/resource/asset/admin/scss/app/`, '')
    .replace(/^\/|\/$/g, '')
    .replace(/\.[^.]*$/, '');

  entry[`asset/admin/css/${output}`] = file;
});

module.exports = {
  entry,
  output: {
    path: `${root}/public`,
    filename: '[name].js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        loader: 'babel-loader'
      },
      {
        test: /\.(sass|scss)$/,
        use: ExtractTextPlugin.extract({
          use: 'css-loader!sass-loader'
        })
      }
    ]
  },
  plugins: [
    new ExtractTextPlugin({
      filename: '[name].css',
      allChunks: true
    })
  ]
};
