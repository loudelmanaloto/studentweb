import { Component, OnInit } from '@angular/core';
import { UserService } from '../services/user.service';
import { DataService } from '../services/data.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-classes',
  templateUrl: './classes.component.html',
  styleUrls: ['./classes.component.scss'],
})
export class ClassesComponent implements OnInit {
  classes: Object;
  constructor(private user: UserService, private ds: DataService, private router: Router) {}

  ngOnInit(): void {
    this.getfacultyclass();
  }

  getfacultyclass() {
    let a = this.user.getUserID();
    this.ds.pull('facultyclass/' + a, null).subscribe((res: any) => {
      this.classes = res.payload;
    });
  }
  viewclass(i) {
    let a = [this.classes[i].cl_classcode,
    this.classes[i].cl_desc,
    this.classes[i].cl_block];
    this.user.setClasscode(a);
    this.router.navigate(['main/classroom']);
  }
}
