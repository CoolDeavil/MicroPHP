const path = require("path");

// const webpack = require("webpack");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyPlugin = require('copy-webpack-plugin');

exports.buildPlugs = (mode) => {
    let filename_ = '';
    if(mode){
        filename_ = "./css/[name].[fullhash].min.css";
    }else{
        filename_ = "./css/[name].min.css";
    }


    return  [

        new HtmlWebpackPlugin({
            inject: false,
            filename: '../Micro/Views/partials/styles.twig',
            templateContent: ({htmlWebpackPlugin}) => `${htmlWebpackPlugin.files.css.map(file => `${file}`, )}`
        }),
        new HtmlWebpackPlugin({
            inject: false,
            filename: '../Micro/Views/partials/scripts.twig',
            templateContent: ({htmlWebpackPlugin}) => `${htmlWebpackPlugin.files.js.map(file => `${file}`, )}`
        }),


        new CopyPlugin({
            patterns:[
                {from: './assets/template/favicon.ico', to:path.resolve(__dirname,'../public') },
                {from: './assets/template/index.php', to:path.resolve(__dirname,'../public') },
                {from: './assets/template/.htaccess', to:path.resolve(__dirname,'../public') },
                {from: './assets/template/map.php', to:path.resolve(__dirname,'../public') },
                {from: './assets/template/data.json', to:path.resolve(__dirname,'../public') },
            ]
        }),


        new MiniCssExtractPlugin({
            filename: filename_
        }),
    ]
}
