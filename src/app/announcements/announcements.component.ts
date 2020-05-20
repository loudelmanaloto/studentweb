import { Component, OnInit, ViewChild } from '@angular/core';
import { DataService } from '../services/data.service';
import { Router } from '@angular/router';
import { UserService } from '../services/user.service';
import { MatTableDataSource } from '@angular/material/table';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { MatDialogConfig, MatDialog } from '@angular/material/dialog';
import { ManageAnnouncementComponent } from '../manage-announcement/manage-announcement.component';

@Component({
  selector: 'app-announcements',
  templateUrl: './announcements.component.html',
  styleUrls: ['./announcements.component.scss'],
})
export class AnnouncementsComponent implements OnInit {
  announcement_obj: Object = [];
  dataSource: any;
  totalpost: number;

  @ViewChild(MatSort, { static: true }) sort: MatSort;
  @ViewChild(MatPaginator, { static: true }) paginator: MatPaginator;

  displayedColumns: string[] = [
    'an_imgdir',
    'an_title',
    'an_timestamp',
    'commentcount',
    'action',
  ];

  constructor(
    private ds: DataService,
    private router: Router,
    private user: UserService,
    private dialog: MatDialog
  ) {}

  ngOnInit(): void {
    this.getData();
  }

  applyFilter(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  getData() {
    this.ds.pull('announcements', null).subscribe((res: any) => {
      this.announcement_obj = res.payload;
      this.dataSource = new MatTableDataSource(res.payload);
      this.dataSource.sort = this.sort;
      this.dataSource.paginator = this.paginator;
      this.totalpost = Object.keys(res.payload).length;
    });
  }

  onAdd() {
    this.onCreate('Add Announcement', 0, 0);
  }

  onEdit(row) {
    console.log(row);
    this.onCreate('Edit Announcement', row, 1);
  }

  onCreate(v_title, payload, edit) {
    const dialogConfig = new MatDialogConfig();
    dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;
    dialogConfig.width = '40%';
    dialogConfig.height = '80%';
    // dialogConfig.maxHeight = '80vh';
    // dialogConfig.maxWidth = '50vw';

    dialogConfig.data = {
      title: v_title,
      record: payload,
      isEdit: edit,
    };
    let dialogRef = this.dialog.open(ManageAnnouncementComponent, dialogConfig);
    dialogRef.afterClosed().subscribe((res) => {
      if (res != undefined) {
        this.dataSource = new MatTableDataSource(res.data);
        this.dataSource.sort = this.sort;
        this.dataSource.paginator = this.paginator;
        this.totalpost = Object.keys(res.data).length;
      }
    });
  }
}
