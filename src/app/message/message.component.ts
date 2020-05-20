import { Component, OnInit } from '@angular/core';
export interface Section {
  name: string;
  updated: Date;
}
@Component({
  selector: 'app-message',
  templateUrl: './message.component.html',
  styleUrls: ['./message.component.scss']
})
export class MessageComponent implements OnInit {
  folders: Section[] = [
    {
      name: 'User1',
      updated: new Date('1/1/16'),
    },
    {
      name: 'User2',
      updated: new Date('1/17/16'),
    },
    {
      name: 'User3',
      updated: new Date('1/28/16'),
    }
  ];


  constructor() { }

  ngOnInit(): void {
  }

}
