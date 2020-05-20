import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class UserService {
  private isUserLoggedIn: boolean = false;
  private fullname: string;
  private token: string;
  private userid: string;
  private website: string = 'http://www.techmatesph.com';
  private subdata = [];
  private postdata = [];
  private viewfulldata = [];
  private classdata: Object;
  constructor() {}

  getUserFullname(): string {
    return this.fullname;
  }
  getUserLoggedIn(): boolean {
    return this.isUserLoggedIn;
  }
  getToken(): string {
    return this.token;
  }
  getUserID(): string {
    return this.userid;
  }
  setToken(token: string): void {
    this.token = token;
  }

  setUserLoggedIn(fullname: string, token: string, id: string): void {
    this.isUserLoggedIn = true;
    this.fullname = fullname;
    this.token = token;
    this.userid = id;
  }
  setSubjectData(data) {
    this.subdata = data;
  }

  getSubjectData() {
    return this.subdata;
  }
  setPostData(data, id) {
    this.postdata = data[id];
  }
  getPostData() {
    return this.postdata;
  }
  setfullData(data) {
    this.viewfulldata = data;
  }
  getfullData() {
    return this.viewfulldata;
  }

  setClasscode(classdata: Object) {
    this.classdata = classdata;
  }

  getClasscode(){
    return this.classdata;
  }
}
