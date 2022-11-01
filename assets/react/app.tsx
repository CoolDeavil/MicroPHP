import React, { Component } from "react";

declare global {
    interface Window {
        userList: any;
    }
}


export class App extends Component  {

    private userList:any = window.userList;

    componentDidMount() {
        console.log("React Component Mounted " , this.userList.length);
    }

    render() {
        return (<table>
                <thead>
                <tr>
                    <td> User </td>
                    <td> Created </td>
                    <td> Last time Edited </td>
                    <td> Last time Logged </td>
                </tr>
                </thead>
                <tbody>
                {this.userList.map(function(user:any, idx:number){
                    return (<tr key={idx}>
                        <td className="created"><strong>{user.email}</strong></td>
                        <td className="lastLog">{user.timeLine}</td>
                        <td className="LastEdit">{user.lastEdited}</td>
                        <td className="created">{user.lastLogged}</td>
                    </tr>)
                })}
                </tbody>
            </table>)
    }

}

export default App;
