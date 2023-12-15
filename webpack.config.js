const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const WebpackBar = require('webpackbar');
const CompressionPlugin = require('compression-webpack-plugin');
const CriticalCssPlugin = require('critical-css-webpack-plugin');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = {
    mode: 'production',
    entry: './resources/ts/main.ts',
    output: {
        filename: 'bundle.js',
        path: path.resolve(__dirname, 'public/js'),
        chunkFilename: '[name].chunk.js',
    },
    resolve: {
        extensions: ['.ts', '.js']
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: 'ts-loader',
                exclude: /node_modules/,
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
                    },
                    mangle: {
                        properties: {
                            regex: /^_/,
                        },
                        keep_fnames: false,
                        keep_classnames: false,
                        toplevel: false,
                        module: true
                    },
                }
            }),
            new CssMinimizerPlugin(),
        ],
    },
    plugins: [
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
