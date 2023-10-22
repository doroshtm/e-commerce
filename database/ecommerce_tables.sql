create table tbl_categoria(
    id_categoria serial primary key,
    nome text not null,
    descricao text
);

create table tbl_produto(
    id_produto serial primary key,
    nome text not null,
    preco decimal(10,2) not null,
    descricao text,
    imagem varchar(255) not null,
    excluido boolean default false,
    data_exclusao timestamp,
    codigovisual varchar(50) not null,
    custo decimal(10,2) not null,
    margem_lucro decimal(10,2) not null,
    icms decimal(10,2) not null,
    categoria integer not null,
    quantidade_estoque integer not null,
    foreign key (categoria) references tbl_categoria(id_categoria)
);

create table tbl_usuario(
    id_usuario serial primary key,
    nome text not null,
    email text not null,
    senha varchar(255) not null,
    data_cadastro timestamp not null,
    telefone varchar(17) not null,
    cpf varchar(14) not null,
    admin boolean default false not null,
    endereco varchar(255),
    cep varchar(9)
);

create table tbl_compra(
    id_compra serial primary key,
    data timestamp not null,
    status varchar(255) not null,
    usuario integer not null,
    cupom varchar(255),
    foreign key (usuario) references tbl_usuario(id_usuario)
);

create table tbl_tmp_compra(
    sessao varchar(255) not null,
    compra integer not null,
    foreign key (compra) references tbl_compra(id_compra),
    PRIMARY KEY (sessao, compra)
);

create table tbl_compra_produto(
    quantidade integer not null,
    produto integer not null,
    compra integer not null,
    FOREIGN KEY (produto) REFERENCES tbl_produto (id_produto),
    FOREIGN KEY (compra) REFERENCES tbl_compra (id_compra),
    PRIMARY KEY (produto, compra)
);