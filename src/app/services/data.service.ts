import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { UserService } from './user.service';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  private url: string = "http://localhost/api-gclms/";

  constructor(private http: HttpClient, private user: UserService) { }

  pull(api, data) {
    let headers: HttpHeaders = new HttpHeaders({
      'Authorization': this.user.getToken(),
      'X-Auth-User': this.user.getUserID()
    });
    let options = { headers };
    // return this.http.post(this.url+api, btoa(unescape(encodeURIComponent(JSON.stringify(data)))), options);
    
    return this.http.post(this.url+api, btoa(unescape(encodeURIComponent(JSON.stringify(data)))));
  }

  uploadFile(api, data) {
    return this.http.post(this.url+api, data);
  }

  login(api, data) {
    return this.http.post(this.url+api, btoa(unescape(encodeURIComponent(JSON.stringify(data)))));
  }
  


}
