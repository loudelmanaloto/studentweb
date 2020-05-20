import { Component, OnInit } from '@angular/core';
import { DataService } from '../services/data.service';
import Swal from 'sweetalert2';
import { UserService } from '../services/user.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  constructor(private ds: DataService, private user: UserService, private router: Router) { }

  ngOnInit(): void {
  }
  public onSubmit(e){
   
      e.preventDefault();
      console.log(e);
      let f = e.target.elements;
      let param1 = f.param1.value;
      let param2 = f.param2.value;
  
      this.ds.login('login', { param1, param2 }).subscribe((data: any) => {
        let load = data.payload;
        this.user.setUserLoggedIn(load.fullname, load.key, load.id);
        this.router.navigate(['main']);
      }, er => {
        Swal.fire ({
          title: er.error.status.remarks,
          text: er.error.status.message,
          icon: 'error'
        });
      });
    
  }

}
