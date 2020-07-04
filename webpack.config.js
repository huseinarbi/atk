const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

module.exports = {
  entry: './src/main.js',
  output: {
    filename: 'js/main.js',
    path: path.resolve(__dirname, 'assets'),
  },
  resolve: {
    alias: {
      '@': path.join(__dirname, '../src'),
      '~': path.join(__dirname, '../src', 'styles'),
    },
  },
  externals: {
    jquery: 'jQuery'
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/[name].css',
      chunkFilename: 'css/[id].css'
    }),
    // new WorkboxPlugin.GenerateSW({
    //   clientsClaim: true,
    //   skipWaiting: true,
    //   runtimeCaching: [{ urlPattern: new RegExp('/'), handler: 'staleWhileRevalidate' }]
    // }),
    new OptimizeCssAssetsPlugin({
      cssProcessorPluginOptions: {
        preset: ['default', { discardComments: { removeAll: true } }]
      }
    }),
  ],
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          // Creates `style` nodes from JS strings
          'style-loader',
          MiniCssExtractPlugin.loader,
          // Translates CSS into CommonJS
          'css-loader',
          // Compiles Sass to CSS
          'sass-loader',
        ],
      },
      {
        test: /.*\.(gif|png|jpe?g|svg)$/i,
        use: ['file-loader']
      }
    ]
  }
};
