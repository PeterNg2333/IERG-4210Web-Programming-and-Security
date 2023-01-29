import ReactDOM from "react-dom/client";
import React from "react";
import { useEffect, useState } from "react";
import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import {useMatch, useParams, useLocation} from "react-router-dom"
import './index.css';


class App extends React.Component {
  render() {
    return(
      <header> 
        <div id="preloader" className="container">
            <div id="loadingImg" className="row">
              <img src={require("./Resource/loading-gif.gif")} alt="Loading"/>
              <h5> Loading...... </h5>
            </div>
        </div>
      </header>
    );
  }
}

const root = ReactDOM.createRoot(document.querySelector('#app'));
root.render(<App name="CUHK Pictures"/>);