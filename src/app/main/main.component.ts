import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.scss']
})
export class MainComponent implements OnInit {

  isOpen: boolean = true;

  constructor(private user: UserService, private router: Router) { }

  ngOnInit(): void {
    if(this.user.getUserLoggedIn()===true){
      this.router.navigate(['main']);
    }
  }

}
