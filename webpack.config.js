const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const WebpackBar = require('webpackbar');
const CompressionPlugin = require('compression-webpack-plugin');
const CriticalCssPlugin = require('critical-css-webpack-plugin');
const { VueLoaderPlugin } = require('vue-loader');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');

module.exports = {
    mode: 'production',
    entry: './resources/ts/main.ts',
    output: {
        filename: 'bundle.js',
        path: path.resolve(__dirname, 'public/js'),
    },
    resolve: {
        extensions: ['.ts', '.js', '.vue'],
        alias: {
            vue$: 'vue/dist/vue.runtime.esm-bundler.js',
        }
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
            },
            {
                test: /\.ts$/,
                use: {
                    loader: 'ts-loader',
                    options: {
                        appendTsSuffixTo: [/\.vue$/],
                    }
                },
                exclude: /node_modules/
            },
            {
                test: /\.js$/,
                use: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader'],
            },
        ],
    },
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                terserOptions: {
                    format: {
                        comments: false,
                    },
                    compress: {
                        drop_console: true,
                        keep_fargs: false,
                        reduce_vars: true,
                        drop_debugger: true,
                        passes: 10,
                    }
                }
            }),
            new CssMinimizerPlugin(),
        ],
    },
    plugins: [
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
            filename: './../css/app.css',
        }),
        new WebpackBar(),
        new CompressionPlugin(),
        new CriticalCssPlugin({
            base: path.resolve(__dirname, 'public'),
            src: 'html/index.html',
            target: 'css/app-critical.css',
            inline: true,
        }),
        // new BundleAnalyzerPlugin()
    ],
};
