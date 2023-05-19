import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreatePedidoComponent } from './create-pedido.component';

describe('CreatePedidoComponent', () => {
  let component: CreatePedidoComponent;
  let fixture: ComponentFixture<CreatePedidoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CreatePedidoComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CreatePedidoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
